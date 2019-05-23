<?php

namespace Model;

class Cards
{
    /** @var TableColumn $id
      * @var TableColumn $name */
    private $id;
    private $name;

    public function __construct()
    {
        $columns = [
            new TableColumn('id', ColumnTypes::BIGINT, 0, true, null, true, true),
            new TableColumn('quantity', ColumnTypes::INT, 11, true, 0)
        ];

        $mysql = new MySQL();
        $mysql->create_table('cards', $columns);

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
}