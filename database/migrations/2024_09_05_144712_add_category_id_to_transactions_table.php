<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter la colonne category_id
            $table->unsignedBigInteger('category_id')->nullable()->after('amount');
            
            // Ajouter une contrainte de clé étrangère pour category_id
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère et la colonne
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
}
