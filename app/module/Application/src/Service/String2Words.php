<?php


namespace Application\Service;

class String2Words
{
    private string $in_string;
    private array $options;

    public function __construct(string $in_string = '', array $options = [])
    {
        $this->options = [
            'words' => "/[-a-zа-я0-9'\"]+/ui",
            'clean_chars' => ["/[\"]+/", "/^'/", "/'$/"],
        ];
        $this->in_string = $in_string;
        $this->options = array_merge($this->options, $options);
    }

    public function __invoke($in_string, array $options = []): array
    {
        $this->in_string = $in_string;
        $this->options = array_merge($this->options, $options);
        return $this->getWords();
    }

    public function getWords(): array
    {
        $result = [];
        if (preg_match_all($this->options['words'], $this->in_string, $matches)) {
            foreach ($matches[0] as $match) {
                $word = $this->clean($match);
                $result[$word] = $match;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    private function clean(string $str): string
    {
        return preg_replace($this->options['clean_chars'], '', $str);
    }
}