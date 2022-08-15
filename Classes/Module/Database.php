<?php

namespace PunktDe\Codeception\Database\Module;

/*
 * This file is part of the PunktDe\Codeception.Database package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */
use Behat\Gherkin\Node\TableNode;
use Codeception\Module\Db;
use Neos\Utility\Arrays;
use Symfony\Component\Yaml\Yaml;
use Codeception\Configuration;
use Neos\Utility\Files;

class Database extends Db
{

    /**
     * @throws \Exception
     */
    public function importDataFromSql(string $filename): void
    {
        $filepath = Files::concatenatePaths([Configuration::testsDir(), $filename]);
        if (array_key_exists('user', $this->_getConfig())
            && array_key_exists('password', $this->_getConfig())
            && array_key_exists('dsn', $this->_getConfig())
            && false !== strpos($this->_getConfig()['dsn'], 'host')
            && false !== strpos($this->_getConfig()['dsn'], 'dbname')
        ) {
            codecept_debug('Starting import');
            $user = $this->_getConfig()['user'];
            $password = $this->_getConfig()['password'];
            $dsn = explode(';', $this->_getConfig()['dsn']);
            $host = explode('=', explode(':', $dsn[0])[1])[1];
            $dbname = explode('=', $dsn[1])[1];
            $cmd = 'mysql -h ' . $host . ' -u ' . $user . ' -p' . $password . ' ' . $dbname . ' < ' . $filepath;
            $result = exec($cmd);
            if ($result !== '') {
                throw new  \Exception('Could not import from sql file ' . $filepath . ' . Error: ' . $result, 1637769333);
            }
            codecept_debug('Finished import');
        } else {
            codecept_debug('Skipping sql file import because configuration is missing. If you want to import from sql file please set dsn, username and password correctly.');
        }
    }

    /**
     * @param string $dataset
     */
    public function importDataset(string $dataset): void
    {
        $fileContents = Files::getFileContents(Files::concatenatePaths([Configuration::testsDir(), $dataset]));

        $datasetArray = Yaml::parse($fileContents);

        if (is_array($datasetArray)) {
            foreach ($datasetArray as $tableName => $listOfItems) {
                $this->_getDriver()->deleteQueryByCriteria($tableName, []);

                if (is_array($listOfItems)) {
                    foreach ($listOfItems as $datarow) {
                        $this->haveInDatabase($tableName, $datarow);
                    }
                }
            }
        }
    }

    /**
     * @param string $table
     * @param TableNode $tableNode
     */
    public function databaseTableShouldContainTable(string $table, TableNode $tableNode): void
    {
        $tableRows = $tableNode->getRows();
        $arrayKeys = $tableRows[0];
        unset($tableRows[0]);

        foreach ($tableRows as $singleRow) {
            $singleRow = array_combine($arrayKeys, $singleRow);
            $this->seeInDatabase($table, $singleRow);
        }
    }

    /**
     * @param string $query
     * @param TableNode $jsonContent
     * @throws \Exception
     */
    public function databaseQueryReturnsFieldWithJson(string $query, TableNode $jsonContent): void
    {
        $pdoStatement = $this->_getDriver()->executeQuery($query, []);
        /** @var $pdoStatement \PDOStatement */

        $this->assertEquals(0, $pdoStatement->errorCode(), sprintf('Execution of query "%s" failed with error "%s" (%d)', $query, implode("\n", $pdoStatement->errorInfo()), $pdoStatement->errorCode()));

        $result = $pdoStatement->fetchAll(\PDO::FETCH_NUM);
        $this->assertEquals(1, count($result), sprintf('Query "%s" returned more than one result row', $query));

        $dataRow = $result[0];
        $this->assertEquals(1, count($dataRow), sprintf('Query "%s" returned more than one result field in data row', $query));

        $data = json_decode($dataRow[0], true);
        if ($data === null) {
            throw new \Exception(sprintf('The result of the query "%s" could not be parsed to JSON', $query), 1432278325);
        }

        $jsonRows = $jsonContent->getRows();
        foreach ($jsonRows as $singleRow) {
            $dataContent = Arrays::getValueByPath($data, $singleRow[0]);
            $this->assertEquals(json_decode($singleRow[1]), $dataContent, sprintf('Failed asserting that "%s" matches expected "%s" for entry "%s"', $dataContent, $singleRow[1], $singleRow[0]));
        }
    }

}
