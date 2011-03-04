<?php
  class fshlTexy extends Texy {
    function blockHandler($invocation, $blocktype, $content, $lang, $modifier) {
        if ($blocktype !== 'block/code') {
                return $invocation->proceed();
        }

        $lang = strtoupper($lang);
        if ($lang == 'JAVASCRIPT') $lang = 'JS';

        $fshl = new fshlParser('HTML_UTF8', P_TAB_INDENT);
        if (!$fshl->isLanguage($lang)) {
                return $invocation->proceed();
        }

        $texy = $invocation->getTexy();
        $content = Texy::outdent($content);
        $content = $fshl->highlightString($lang, $content);
        $content = $texy->protect($content, Texy::CONTENT_BLOCK);

        $elPre = TexyHtml::el('pre');
        if ($modifier) $modifier->decorate($texy, $elPre);
        $elPre->attrs['class'] = strtolower($lang);

        $elCode = $elPre->create('code', $content);

        return $elPre;
    }

  }
