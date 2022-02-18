<?php

namespace elegance;

/** Manipula diretórios representados por referencias */
abstract class Dir
{

    /**
     * Cria um diretório no sistema
     * @param string $path Caminho para o diretório que deve ser criado
     */
    public static function create($path)
    {
        $path = self::getOnly($path);

        if (!is_dir($path)) {
            $createList = explode('/', $path);
            $createPath = '';
            foreach ($createList as $creating) {
                $createPath = ($createPath == '') ? $creating : self::getOnly("$createPath/$creating");
                if ($createPath != '.' && $createPath != '..' && !empty($createPath) && !self::check($createPath)) {
                    mkdir($createPath);
                }
            }
            return is_dir($path);
        }
    }

    /**
     * Remove um diretório do sistema
     * @param string $path Caminho para o diretório que deve ser removido
     * @param string $path Se o modo Recursivo deve ser utilizado
     */
    public static function remove($path, $recursive = false)
    {
        $path = self::getOnly($path);

        if (is_dir($path)) {
            if ($recursive || empty(self::seek_for_all($path))) {
                $drop = function ($path, $function) {
                    foreach (scandir($path) as $iten) {
                        if ($iten != '.' && $iten != '..') {
                            if (is_dir("$path/$iten")) {
                                $function("$path/$iten", $function);
                            } else {
                                unlink("$path/$iten");
                            }
                        }
                    }
                    rmdir($path);
                };
                $drop($path, $drop);
            }
        }

        return !is_dir($path);
    }

    /**
     * Cria uma copia de um diretório
     * @param string $path_from Diretório que deve ser copiadao
     * @param string $path_for Local para onde o diretório deve ser copiado
     * @param boolean $replace Caso deva subistituir os arquivos existenstes
     */
    public static function copy($path_from, $path_for, $replace = false)
    {
        if (self::check($path_from)) {
            self::create($path_for);
            $copy = function ($from, $for, $replace, $function) {
                foreach (self::seek_for_dir($from) as $dir) {
                    $function("$from/$dir", "$for/$dir", $replace, $function);
                }
                foreach (self::seek_for_file($from) as $file) {
                    File::copy("$from/$file", "$for/$file", $replace);
                }
            };
            $copy($path_from, $path_for, $replace, $copy);
            return true;
        }
    }

    /**
     * Altera o local de um diretório
     * @param string $path_from Diretório que deve ser movido
     * @param string $path_for Local para onde o diretório deve ser movido
     */
    public static function move($path_from, $path_for)
    {
        if (!self::check($path_from) && self::check($path_from)) {
            $path_from = path($path_from);
            $path_for  = path($path_for);
            return boolval(rename($path_from, $path_for));
        }
    }

    /**
     * Vasculha um diretório em busca de arquivos
     * @param string $path Caminho do diretório que deve ser vasculhado
     * @param string $recursive Caso deva buscar por arquivos em subdiretórios
     * @return string[] Array com nome de todos os arquivos do diretório
     */
    public static function seek_for_file($path, $recursive = false)
    {
        $return = [];
        foreach (self::seek_for_all($path, $recursive) as $item) {
            if (File::check("$path/$item")) {
                $return[] = $item;
            }
        }
        return $return;
    }

    /**
     * Vasculha um diretório em busca de diretórios
     * @param string $path Caminho do diretório que deve ser vasculhado
     * @param string $recursive Caso deva buscar por diretórios em subdiretórios
     * @return string[] Array com nome de todos os diretórios do diretório
     */
    public static function seek_for_dir($path, $recursive = false)
    {
        $return = [];
        foreach (self::seek_for_all($path, $recursive) as $item) {
            if (self::check("$path/$item")) {
                $return[] = $item;
            }
        }
        return $return;
    }

    /**
     * Vasculha um diretório em busca de arquivos e diretórios
     * @param string $path Caminho do diretório que deve ser vasculhado
     * @param string $recursive Caso deva vasculhar subdiretórios
     * @return string[] Array com nome de todos os arquivos e diretórios do diretório
     */
    public static function seek_for_all($path, $recursive = false)
    {
        $path   = self::getOnly($path);
        $return = [];
        if (is_dir($path)) {
            foreach (scandir($path) as $item) {
                if ($item != '.' && $item != '..') {
                    $return[] = $item;
                    if ($recursive && self::check("$path/$item")) {
                        foreach (self::seek_for_all("$path/$item", true) as $subItem) {
                            $return[] = "$item/$subItem";
                        }
                    }
                }
            }
        }
        return $return;
    }

    /** Retorna um caminho sem referenciar arquivos */
    public static function getOnly($path)
    {
        $path = path($path);
        if ($path != '.') {
            $path = explode('/', $path);
            if (strpos(end($path), '.') !== false) {
                array_pop($path);
            }
            $path = implode('/', $path);
        }
        return $path;
    }

    /** Verifica se um diretório existe */
    public static function check($path)
    {
        return is_dir(path($path));
    }
}
