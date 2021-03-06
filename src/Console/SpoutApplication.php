<?php
/**
 * This file is part of the Mackstar.Spout package.
 *
 * (c) Richard McIntyre <richard.mackstar@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mackstar\Spout\Console;

use Phinx\Console\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SpoutApplication extends Application
{
    /**
     * Initialize the Spout console application.
     */
    public function __construct()
    {
        parent::__construct("Spout Console", "0.0.1");

        $this->add(new Install());
        $this->add(new PreTest());
        $this->add(new CreateMigration());
        $this->add(new RunMigration());
        $this->add(new RollbackMigration());
    }
     
    /**
     * Runs the current application.
     *
     * @param InputInterface $input An Input instance
     * @param OutputInterface $output An Output instance
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        // always show the version information except when the user invokes the help
        // command as that already does it
        if (false === $input->hasParameterOption(array('--help', '-h')) && null !== $input->getFirstArgument()) {
            $output->writeln($this->getLongVersion());
            $output->writeln('');
        }
         
        return parent::doRun($input, $output);
    }
}
