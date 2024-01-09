<?php

use yii\db\Migration;

/**
 * Class m210206_071556_create_start_tables
 */
class m210206_071556_create_start_tables extends Migration
{
    /**
     * {@inheritdoc}
     */

    // DATE
    // CHARACTER VARYING(50)
    // INTEGER NOT NULL
    // TIMESTAMP
    // DOUBLE PRECISION

    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.users
        (
            id serial NOT NULL,
            chat_id INTEGER,
            username CHARACTER VARYING(50),
            name CHARACTER VARYING(50),
            second_name CHARACTER VARYING(50),
            phone_number CHARACTER VARYING(50),
            type SMALLINT,
            status SMALLINT,
            link CHARACTER VARYING(100),
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.step
        (
            id serial NOT NULL,
            chat_id INTEGER,
            step_1 INTEGER,
            step_2 INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS public.last_id
        (
            id serial NOT NULL,
            chat_id INTEGER,
            last_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS public.branch
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.team
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            branch_id INTEGER,
            user_id INTEGER,
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.category
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            branch_id INTEGER,
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.clients
        (
            id serial NOT NULL,
            full_name CHARACTER VARYING(50),
            phone_number CHARACTER VARYING(25),
            chat_id INTEGER,
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.orders
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            branch_id INTEGER,
            user_id INTEGER,
            client_id INTEGER,
            created_date TIMESTAMP,
            dead_line TIMESTAMP,
            status SMALLINT,
            feedback_user DOUBLE PRECISION,
            feedback_client DOUBLE PRECISION,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.order_materials
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            file CHARACTER VARYING(50),
            type CHARACTER VARYING(10),
            order_id INTEGER,
            user_id INTEGER,
            created_date TIMESTAMP,
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.sections
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            created_date DATE,
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.section_orders
        (
            id serial NOT NULL,
            order_id INTEGER,
            section_id INTEGER,
            enter_date TIMESTAMP,
            exit_date TIMESTAMP,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.order_step
        (
            id serial NOT NULL,
            order_id INTEGER,
            section_id INTEGER,
            deadline TIMESTAMP,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

    }














    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210206_071556_create_start_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210206_071556_create_start_tables cannot be reverted.\n";

        return false;
    }
    */
}
