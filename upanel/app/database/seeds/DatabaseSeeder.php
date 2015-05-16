<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();

        ////insertamos los usuarios
        $this->call('Seed_IntanciasMetadatos');
        //mostramos el mensaje de que los usuarios se han insertado correctamente
        $this->command->info('Configuración de instancia principal creada!');
         ////insertamos los usuarios
        $this->call('Seed_Usuarios');
        //mostramos el mensaje de que los usuarios se han insertado correctamente
        $this->command->info('Usuarios registrados!');
    
        
    }

}

//clase para insertar usuarios
class Seed_IntanciasMetadatos extends Seeder {

    public function run() {

        DB::table('instanciasMetadatos')->insert(array(
            'instancia' => '0',
            "clave" => "periodoPrueba_activado",
            "valor" => "1",
        ));

        DB::table('instanciasMetadatos')->insert(array(
            'instancia' => '0',
            "clave" => "periodoPrueba_numero_dias",
            "valor" => "7",
        ));
    }

}

//clase para insertar usuarios
class Seed_Usuarios extends Seeder {

    public function run() {

        DB::table('usuarios')->insert(array(
            'nombres' => 'John Heider',
            "apellidos" => "Ospina Arzuaga",
            "email" => "jhonospina150@gmail.com",
            "email_confirmado" => "1",
            "password" => Hash::make("123456"),
            "celular" => "3006422992",
            "tipo" => "AD"
        ));
    }

}
