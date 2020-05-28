<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class BaseDinamica extends Model
{
    public static function connexionDynamicSon($nombreBDNueva = null)
    {
        Config::set("database.connections.pgsql.host", 'localhost');
        if (is_null($nombreBDNueva)) {
            $nombreDB = auth()->user()->despacho;
            Config::set("database.connections.pgsql.database", $nombreDB);
        }else{
            Config::set("database.connections.pgsql.database", $nombreBDNueva);
        }
        Config::set("database.connections.pgsql.username", 'B4nC0');
        Config::set("database.connections.pgsql.password", '');
        Schema::connection('pgsql')->getConnection()->reconnect();
    }


}
