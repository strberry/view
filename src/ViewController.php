<?php

class ViewController implements IController {

    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    private function renderTemplateVars(string $file, $pageVars) : string {
        $rendered = file_get_contents("./views/$file.php");

        $templateVars = [];
        preg_match_all('/{{\s*\$\w+\s*}}/', $rendered, $templateVars);
        $templateVars = array_pop($templateVars);
        $templateVars = array_unique($templateVars);

        foreach ($templateVars as $templateVar) {
            preg_match('/\w+/', $templateVar, $var);
            $var = array_pop($var);
            $rendered = str_replace($templateVar, $pageVars[$var], $rendered);
        }

        return $rendered;
    }

    private function renderComponents(string $templateVarsRendered) : string
    {
        $rendered = $templateVarsRendered;

        $componentsWanted = [];
        preg_match_all('/{{\s*\w+,.*?\s*}}/', $templateVarsRendered, $componentsWanted);
        $componentsWanted = $componentsWanted[0];

        foreach ($componentsWanted as $currentComponent) {
            preg_match('/\w+,\s*.* /', $currentComponent, $componentRaw);
            $componentRaw = $componentRaw[0];
            $componentRaw = explode(',', $componentRaw);
            $componentName = $componentRaw[0];

            $componentParameters = array_slice($componentRaw, 1, sizeof($componentRaw));
            $componentParameters = implode(',', $componentParameters);
            $componentParameters = json_decode($componentParameters, true);

            $componentRendered = $this->renderTemplateVars("components/$componentName", $componentParameters);
            $rendered = str_replace($currentComponent, $componentRendered, $rendered);
        }

        return $rendered;
    }

    function respond($data): string
    {
        $templateVarsRendered = $this->renderTemplateVars($this->fileName, $data);
        return $this->renderComponents($templateVarsRendered);
    }
}