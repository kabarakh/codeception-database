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
     * @Given data from sql file :filepath is imported
     * @param string $filepath
     */
    public function iImportDataFromSql(string $filepath): void
    {
        $this->importDataFromSql($filepath);
    }

    /**
     * @Given dataset :dataset is imported
     * @param string $dataset
     */
    public function iImportDataset(string $dataset): void
    {
        $this->importDataset($dataset);
    }

    /**
     * @Then the database table :table should contain
     * @param string $table
     * @param TableNode $tableNode
     */
    public function databaseTableShouldContain(string $table, TableNode $tableNode): void
    {
        $this->databaseTableShouldContainTable($table, $tableNode);
    }

    /**
     * @Then the database query :query should return a json field with data
     * @param string $query
     * @param TableNode $tableNode
     */
    public function databaseQueryShouldReturnJsonField(string $query, TableNode $tableNode): void {
        $this->databaseQueryReturnsFieldWithJson($query, $tableNode);
    }

    /**
     * @Then the database has :number rows in table :table
     * @param int|string $number
     * @param string $table
     */
    public function seeNumberOfRows(int|string $number, string $table): void
    {
        $this->seeNumRecords((int)$number, $table);
    }

    /**
     * @Then there are :expectedNumber rows matching :criteria in table :table
     * @param int $expectedNumber
     * @param string $table
     * @param array $criteria
     */
    public function seeNumRecordsMatching(int|string $expectedNumber, string $table, array $criteria = []): void
    {
        $this->seeNumRecords((int)$expectedNumber, $table, $criteria);
    }

    /**
     * @param string $query
     * @param TableNode $field
     * @return void
     * 
     * @Then the database query :query should return the fields
     */
    public function databaseQueryShouldReturnFields(string $query, TableNode $fields)
    {
        $this->databaseQueryReturnsFields($query, $fields);
    }
}
