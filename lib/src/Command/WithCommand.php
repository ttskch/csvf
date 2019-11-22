<?php

declare(strict_types=1);

namespace Ttskch\Csvf\Command;

use League\Csv\CharsetConverter;
use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WithCommand extends Command
{
    public function configure() : void
    {
        $this
            ->setName('with')
            ->setDescription('Formats CSV with given parameters')
            ->addArgument('infile', InputArgument::REQUIRED, 'Path to input CSV file')
            ->addOption('delimiter', 'd', InputOption::VALUE_REQUIRED, 'Specify delimiter character (,|TAB)')
            ->addOption('encoding', 'e', InputOption::VALUE_REQUIRED, 'Specify encoding (see https://www.php.net/manual/mbstring.supported-encodings.php)')
            ->addOption('newline', 'l', InputOption::VALUE_REQUIRED, 'Specify newline code (LF|CRLF)');
    }

    public function execute(InputInterface $input, OutputInterface $output) : void
    {
        $this->validateInputOptions($input);

        $inFilePath = realpath($input->getArgument('infile'));
        $tmpInFilePath = tempnam(sys_get_temp_dir(), 'ttskch-csvf-');
        $outFilePath = sprintf('%s/%s_out.%s', pathinfo($inFilePath)['dirname'], pathinfo($inFilePath)['filename'], pathinfo($inFilePath)['extension']);

        $inContents = file_get_contents($inFilePath);
        $originalEncoding = mb_detect_encoding($inContents, 'ASCII, SJIS-win, EUC-JP, UTF-8, UTF-16LE, UTF-16BE', true);
        file_put_contents($tmpInFilePath, mb_convert_encoding($inContents, 'UTF-8', $originalEncoding));

        $reader = Reader::createFromPath($tmpInFilePath, 'r');
        $reader->setHeaderOffset(0);

        $writer = Writer::createFromStream(fopen('php://temp', 'r+'));
        $writer->setDelimiter($input->getOption('delimiter') ?: $reader->getDelimiter());
        CharsetConverter::addTo($writer, 'UTF-8', $input->getOption('encoding') ?: $originalEncoding);
        $writer->setNewline($input->getOption('newline') ?: "\n");

        // todo: when export csv as "UTF-16 and CRLF" some kanji characters is broken
        // @see https://qiita.com/cobachan/items/5e131ce5c88fba9d009c

        $writer->insertOne($reader->getHeader());
        $writer->insertAll($reader->getRecords());

        file_put_contents($outFilePath, $writer->getContent());

        $output->writeln(sprintf('Written into <fg=green>%s</>', $outFilePath));
    }

    private function validateInputOptions(InputInterface &$input) : void
    {
        switch ($input->getOption('delimiter')) {
            case 'TAB':
            case 'tab':
                $input->setOption('delimiter', "\t");
                break;
            case ',':
                $input->setOption('delimiter', ',');
                break;
            default:
                break;
        }

        switch ($input->getOption('newline')) {
            case 'LF':
                $input->setOption('newline', "\n");
                break;
            case 'CRLF':
                $input->setOption('newline', "\r\n");
                break;
            default:
                break;
        }
    }
}
