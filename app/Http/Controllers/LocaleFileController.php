<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Lang;

class LocaleFileController extends Controller
{
    private $lang;
    private $file;
    private $key;
    private $value;
    private $path;
    private $arrayLang = array();

//------------------------------------------------------------------------------
// Add or modify lang files content
//------------------------------------------------------------------------------

    public function changeLang(Request $request) 
    {
// Process and prepare you data as you like.]

        $this->lang = '';
        $this->file = 'public';
        $this->key = $request->key;
        $this->value = $request->value;
        $this->changeLangFileContent();

        
        //$this->deleteLangFileContent();
        $as = collect(Lang::get('public'));
        return redirect()->back(); 
    }

//------------------------------------------------------------------------------
// Add or modify lang files content
//------------------------------------------------------------------------------

    private function changeLangFileContent() 
    {
        $this->read();
        $this->arrayLang[$this->key] = $this->value;
        $this->save();
    }

//------------------------------------------------------------------------------
// Delete from lang files
//------------------------------------------------------------------------------

    private function deleteLangFileContent() 
    {
        $this->read();
        unset($this->arrayLang[$this->key]);
        $this->save();
    }

//------------------------------------------------------------------------------
// Read lang file content
//------------------------------------------------------------------------------

    private function read() 
    {
        if ($this->lang == '') $this->lang = App::getLocale();
        $this->path = base_path().'/lang/'.$this->lang.'/'.$this->file.'.php';
        // dd( $this->path);
        $this->arrayLang = Lang::get($this->file);
        // dd($this->arrayLang);


        // dd($this->file, $this->arrayLang);

        if (gettype($this->arrayLang) == 'string') $this->arrayLang = array();
    }

//------------------------------------------------------------------------------
// Save lang file content
//------------------------------------------------------------------------------

    private function save() 
    {
        $content = "<?php\n\nreturn\n[\n";

        foreach ($this->arrayLang as $this->key => $this->value) 
        {
            $content .= "\t'".$this->key."' => '".$this->value."',\n";
        }

        $content .= "];";

        file_put_contents($this->path, $content);

    }


    public function getValue(Request $request)
    {
        $id = $request->id;
        $languageVariable = collect(Lang::get('public'));
       return  $data = $languageVariable[$id];
    }
}
