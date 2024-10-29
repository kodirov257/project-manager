<?php

namespace App\Command;

use App\Model\User\Entity\User\Role as RoleValue;
use App\Model\User\UseCase\Role;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'user:role',
    description: 'Changes user role',
)]
class RoleCommand extends Command
{
    public function __construct(
        private readonly UserFetcher $users,
        private readonly ValidatorInterface $validator,
        private readonly Role\Handler $handler,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, new Question('Email: '));

        if (!$user = $this->users->findByEmail($email)) {
            throw new \LogicException('User is not found.');
        }

        $command = new Role\Command($user->id);

        $roles = [RoleValue::USER, RoleValue::ADMIN];
        $command->role = $helper->ask($input, $output, new ChoiceQuestion('Role: ', $roles, 0));

        $violations = $this->validator->validate($command);

        if ($violations->count()) {
            foreach ($violations as $violation) {
                $output->writeln('<error>' . $violation->getPropertyPath() . ':' . $violation->getMessage() . '</error>');
            }
            return Command::FAILURE;
        }

        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
