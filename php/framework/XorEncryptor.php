<?php
namespace php\framework;

use php\gui\UXNode;

/**
 * Принадлежит FXEdition
 * Класс XorFileEncryptor предоставляет функционал для XOR-шифрования данных и файлов.
 * Авторы: Максим С. (оригинальный код), Артём.К (Demon) (переработанный код для FXE).
 */
class XorEncryptor
{
    /**
     * Счетчик для преобразования символов.
     *
     * @var int
     */
    public $counter;

    /**
     * Ключ для XOR-шифрования.
     *
     * @var string[]
     */
    private $key = ['F', 'X', 'E', 'D', 'E', 'V', 'E', 'L', 'N', 'E', 'X', 'T'];

    /**
     * Получить текущий ключ.
     *
     * @return string[]
     */
    public function getKey()
    {
        return str_split($this->key);
    }

    /**
     * Изменить ключ.
     *
     * @param string[] $key Новый ключ
     */
    public function changeKey($key)
    {
        if ($key !== null) {
            $this->key = $key;
        }else {
        throw new Exception("The key is null");
}
    }

    /**
     * Конструктор класса XorFileEncryptor.
     *
     * @param string[] $key Начальный ключ
     */
    public function __construct($key = null)
    {
        if ($key !== null) {
            $this->key = $key;
        }
    }

    /**
     * Преобразование символа с использованием XOR.
     *
     * @param string $char Входной символ
     * @return string Преобразованный символ
     */
    private function transformChar($char)
    {
        $a = ord($char);
        $b = ord($this->key[++$this->counter % 32]) & 15;
        $xor = $a ^ $b;

        return chr($xor);
    }

    /**
     * Шифрование данных.
     *
     * @param string $data Входные данные
     * @return string Зашифрованные данные
     */
    public function encryptData($data): string
    {
        $dataBytes = str_split($data);
        $this->counter = 0;

        $encryptedBytes = array_map([$this, 'transformChar'], $dataBytes);

        $text = implode('', $encryptedBytes);
        return base64_encode($text);
    }

    /**
     * Дешифрование данных.
     *
     * @param string $data Зашифрованные данные
     * @return string Расшифрованные данные
     */
    public function decryptData($data): string
    {
        $data = base64_decode($data);
        $this->counter = 0;
        $dataBytes = str_split($data);
        $decryptedBytes = array_map([$this, 'transformChar'], $dataBytes);

        return implode('', $decryptedBytes);
    }
}