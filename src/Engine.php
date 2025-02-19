<?php
namespace Waterloobae\CrowdmarkDashboard;

class Engine
{
    public function render(string $viewsName, array $data = []): string
    {
        $path = __DIR__ . '/views/' . $viewsName . '.php';
        die($path);
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('View not found');
        }
        $contents = file_get_contents($path);
        foreach ($data as $key => $value) {

            $contents = str_replace(
                '{'.$key.'}', (string)$value, $contents
            );
}
        return $contents;
    }
}