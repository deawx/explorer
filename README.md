# EXPLORER

Gerencia a estrutura de pastas e arquivos do projeto

    composer require elegance/explorers


---

### Manipulação de diretórios**create**

Cria um diretório no sistema
    
    Dir::create($path)

**remove**
Remove um diretório do sistema
    
    Dir::remove($path, $recursive = false)

**copy**
Cria uma copia de um diretório
    
    Dir::copy($path_from, $path_for, $replace = false)

**move**
Altera o local de um diretório
    
    Dir::move($path_from, $path_for)

**seek_for_file**
Vasculha um diretório em busca de arquivos
    
    Dir::seek_for_file($path, $recursive = false)

**seek_for_dir**
Vasculha um diretório em busca de diretórios
    
    Dir::seek_for_dir($path, $recursive = false)

**seek_for_all**
Vasculha um diretório em busca de arquivos e diretórios
    
    Dir::seek_for_all($path, $recursive = false) 

**getOnly**
Retorna um caminho sem referenciar arquivos
    
    Dir::getOnly($path) 

**check**
Verifica se um diretório existe
    
    Dir::check($path)

---

### Manipulação de arquivos

**create**
Cria um arquivo de texto no projeto

    File::create($path, $content, $recreate = false)

**remove**
Remove um arquivo do servidor

    File::remove($path)

**copy**
Cria uma copia de um arquivo

    File::copy($path_from, $path_for, $recreate = false)

**move**
Altera o local de um arquivo

    File::move($path_from, $path_for, $replace = false)

**getOnly**
Retorna apenas o nome do arquivo

    File::getOnly($path)

**getExtension**
Retorna a extensão do arquivo

    File::getExtension($path)

**getName**
Retorna o nome do arquivo sem a extensão

    File::getName($path)

**check**
Verifica se um arquivo existe

    File::check($path)

**ensure_extension**
Garante que a referencia aponta para um arquivo com uma das extensões forneceidas

    File::ensure_extension(&$path, $extensions = 'php')

