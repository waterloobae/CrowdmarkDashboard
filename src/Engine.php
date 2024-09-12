<?php
namespace Waterloobae\CrowdmarkDashboard;

class Engine
{
    public function render(string $path, array $data = []): string
    {
        $contents = file_get_contents($path);
        foreach ($data as $key => $value) {
            $contents = str_replace(
                '{'.$key.'}', $value, $contents
            );
}
        return $contents;
    }
}