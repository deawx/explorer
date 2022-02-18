<?php

namespace elegance;

/** Manipula arquivos representados por referencias */
abstract class File
{

    /**
     * Cria um arquivo de texto no projeto
     * @param string $path Caminho para o arquivo que deve ser criado
     * @param string $content Conteúdo que deve ser inserido no arquivo
     * @param boolean $recreate Caso deva remover e criar um novo arquivo
     */
    public static function create($path, $content, $recreate = false)
    {
        if ($recreate || !self::check($path)) {
            $path = path($path);
            Dir::create($path);
            $fp = fopen($path, 'w');
            fwrite($fp, $content);
            fclose($fp);
            return true;
        }
    }

    /**
     * Remove um arquivo do servidor
     * @param string $path Caminho para o arquivo que deve ser removido
     */
    public static function remove($path)
    {
        if (self::check($path)) {
            $path = path($path);
            unlink($path);
        }
        return !is_file($path);
    }

    /**
     * Cria uma copia de um arquivo
     * @param string $path_from Caminho do arquivo que deve fornecer o conteúdo
     * @param string $path_for Caminho do aquivo que deve receber o conteúdo
     * @param boolean $recreate Caso deva remover e criar um novo arquivo
     */
    public static function copy($path_from, $path_for, $recreate = false)
    {
        if ($recreate || !self::check($path_for)) {
            if (self::check($path_from)) {
                Dir::create($path_for);
                boolval(copy(path($path_from), path($path_for)));
            }
        }
    }

    /**
     * Altera o local de um arquivo
     * @param string $path_from Caminho do arquivo que deve ser movido
     * @param string $path_for Caminho para o novo local do arquivo
     * @param boolean $replace Caso deva subistituir arquivos existentes
     */
    public static function move($path_from, $path_for, $replace = false)
    {

        if ($replace || !self::check($path_for)) {
            if (self::check($path_from)) {
                Dir::create($path_for);
                return boolval(rename(path($path_from), path($path_for)));
            }
        }
    }

    /** Retorna apenas o nome do arquivo */
    public static function getOnly($path)
    {
        $path = path($path);
        $path = explode('/', $path);
        return array_pop($path);
    }

    /** Retorna a extensão do arquivo */
    public static function getExtension($path)
    {
        $path = explode('.', $path);
        return toCase_lower(array_pop($path));
    }

    /** Retorna o nome do arquivo sem a extensão */
    public static function getName($path)
    {
        $path = explode('.', self::getOnly($path));
        array_pop($path);
        return implode('.', $path);
    }

    /** Verifica se um arquivo existe */
    public static function check($path)
    {
        $path = path($path);
        return is_file($path);
    }

    /**
     * Garante que a referencia aponta para um arquivo com uma das extensões forneceidas
     * @param string $path Referencia do arquivo
     * @param string|string[] $extensions Extensoes que devem ser permitidas
     * Caso nenhum extensão for encontrada, irá adicionar a primeira extensão a referencia
     */
    public static function ensure_extension(&$path, $extensions = 'php')
    {
        ensure_array($extensions);
        $path_ex = explode('.', $path);
        $path_ex = array_pop($path_ex);
        if (!in_array($path_ex, $extensions)) {
            $path .= '.' . $extensions[0];
        }
    }
}
