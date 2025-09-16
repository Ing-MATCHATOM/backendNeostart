<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscriptionTable extends Migration
{
    public function up()
{
    Schema::table('inscription', function (Blueprint $table) {
        $table->string('prenom_nom')->nullable();
        $table->string('nom_famille')->nullable();
        $table->string('mot_de_passe');
        $table->boolean('confirmation')->default(0);
    });
}

public function down()
{
    Schema::table('inscription', function (Blueprint $table) {
        $table->dropColumn(['prenom_nom', 'nom_famille', 'mot_de_passe', 'confirmation']);
    });
}

}
