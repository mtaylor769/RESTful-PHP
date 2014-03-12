<?php
class ApiView {
    public function render($content) {
        header('Content-Type: text/plain; charset=utf8');
        echo $content;
        return true;
    }
}
?>