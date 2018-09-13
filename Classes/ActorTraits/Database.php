<?php

namespace PunktDe\Codeception\Database\ActorTraits;

/*
 * This file is part of the PunktDe\Codeception-Database package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Behat\Gherkin\Node\TableNode;

trait Database
{
    /**
     * @Given dataset :dataset is imported
     */
    public function iImportDataset($dataset)
    {
        $this->importDataset($dataset);
    }

    /**
     * @Then the database table :table should contain
     */
    public function databaseTableShouldContain($table, TableNode $tableNode)
    {
        $this->databaseTableShouldContainTable($table, $tableNode);
    }

    /**
     * @Then the database query :query should return a json field with data
     */
    public function databaseQueryShouldReturnJsonField(string $query, TableNode $tableNode) {
        $this->databaseQueryReturnsFieldWithJson($query, $tableNode);
    }

    /**
     * @Then the database has :number rows in table :table
     */
    public function seeNumberOfRows(int $number, string $table)
    {
        $this->seeNumRecords($number, $table);
    }
}
