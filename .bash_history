composer install
php artisan key:generate
artisan
php artisan
php artisan migrate
ls
php artisan make:migration create_usuarios_table
php artisan make:migration create_vagas_table
php artisan make:migration create_vaga_anexos_table
php artisan make:migration create_vaga_retificacoes_table
php artisan make:migration create_vagas_auditoria_table
php artisan make:migration create_sistema_logs_table
php artisan make:model Usuario
php artisan make:model Vaga
php artisan make:model VagaAnexo
php artisan make:model VagaRetificacao
php artisan make:model VagaAuditoria
php artisan make:model SistemaLog
php artisan make:seeder UsuariosSeeder
php artisan migrate
php artisan db:seed
php artisan make:provider AuthServiceProvider
