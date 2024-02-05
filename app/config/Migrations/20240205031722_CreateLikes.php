<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLikes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('likes');
        $table->addColumn('user_id', 'integer')
            ->addColumn('article_id', 'integer')
            ->addColumn('created_at', 'datetime', [
                'default' => null,
                'null' => true
            ])
            ->addColumn('updated_at', 'datetime', [
                'default' => null,
                'null' => true
            ])
            ->addIndex(['user_id', 'article_id'], ['unique' => true])
            ->create();
    }
}
