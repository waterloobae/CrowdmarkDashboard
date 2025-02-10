<?php
namespace Waterloobae\CrowdmarkDashboard;

class Engine
{
    public function render(string $viewsName, array $data = []): string
    {
        $path = __DIR__ . '/views/' . $viewsName . '.php';
        $contents = file_get_contents($path);
        foreach ($data as $key => $value) {
            $contents = str_replace(
                '{'.$key.'}', $value, $contents
            );
}
        return $contents;
    }
}