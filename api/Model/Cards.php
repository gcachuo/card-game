<?php

namespace Model;

class Cards
{
    /** @var TableColumn $id
     * @var TableColumn $name
     */
    private $id;
    private $name;

    public function __construct()
    {
        $columns = [
            new TableColumn('id', ColumnTypes::BIGINT, 0, true, null, true, true),
            new TableColumn('quantity', ColumnTypes::INT, 11, true, 0)
        ];

        $mysql = new MySQL();
        if ($mysql->create_table('cards', $columns)) {
            $this->insert(1, 5);
            $this->insert(2, 2);
            $this->insert(3, 2);
            $this->insert(4, 2);
            $this->insert(5, 2);
            $this->insert(6, 1);
            $this->insert(7, 1);
            $this->insert(8, 1);
        }

        $columns = [
            new TableColumn('cards_id', ColumnTypes::BIGINT, 0, true),
            new TableColumn('language', ColumnTypes::VARCHAR, 5),
            new TableColumn('name', ColumnTypes::VARCHAR, 100),
            new TableColumn('description', ColumnTypes::VARCHAR, 255),
        ];
        $sql = <<<sql
create unique index cards_locale_cards_id_uindex on cards_locale (cards_id);
sql;
        $mysql->create_table('cards_locale', $columns, $sql);
    }

    function insert($id, $quantity)
    {
        $mysql = new MySQL();
        $mysql->prepare("insert into cards(id, quantity) VALUES (?, ?);", ['ii', $id, $quantity]);
    }
}