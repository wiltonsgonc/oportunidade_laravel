<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vaga;

class EncerrarVagasVencidas extends Command
{
    protected $signature = 'vagas:encerrar-vencidas';

    protected $description = 'Encerra automaticamente as vagas cuja data limite expirou';

    public function handle(): int
    {
        $this->info('Verificando vagas vencidas...');

        $contagem = Vaga::encerrarVagasVencidas();

        if ($contagem > 0) {
            $this->info("{$contagem} vaga(s) encerrada(s) automaticamente.");
        } else {
            $this->info('Nenhuma vaga para encerrar.');
        }

        return Command::SUCCESS;
    }
}
