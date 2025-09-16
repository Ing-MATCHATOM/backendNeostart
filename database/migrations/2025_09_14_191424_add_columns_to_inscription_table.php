<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInscriptionTable extends Migration
{
public function up()
{
    Schema::table('inscription', function (Blueprint $table) {
        if (!Schema::hasColumn('inscription', 'prenom_nom')) {
            $table->string('prenom_nom')->nullable();
        }
        if (!Schema::hasColumn('inscription', 'nom_famille')) {
            $table->string('nom_famille')->nullable();
        }
        if (!Schema::hasColumn('inscription', 'mot_de_passe')) {
            $table->string('mot_de_passe');
        }
        if (!Schema::hasColumn('inscription', 'confirmation')) {
            $table->boolean('confirmation')->default(0);
        }
    });
}

public function down()
{
    Schema::table('inscription', function (Blueprint $table) {
        $table->dropColumn(['prenom_nom', 'nom_famille', 'mot_de_passe', 'confirmation']);
    });
}

}
