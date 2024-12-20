<?php

namespace App\Command;

use App\Model\User\UseCase\SignUp\Confirm;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'user:confirm',
    description: 'Confirms signed up user',
)]
class ConfirmCommand extends Command
{
    public function __construct(
        private readonly UserFetcher $users,
        private readonly Confirm\Manual\Handler $handler,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, new Question('Email: '));

        if (!$user = $this->users->findByEmail($email)) {
            throw new \LogicException('User is not found.');
        }

        $command = new Confirm\Manual\Command($user->id);
        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
