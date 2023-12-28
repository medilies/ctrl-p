<?php

use Medilies\CtrlP\AtPage;

test('compilePageCss full assertion')
    ->expect(
        AtPage::new()
            ->margins('1cm 2cm 3cm 4cm')
            ->paperSize('210mm', '297mm') // A4
            ->toString()
    )
    ->toBeString()
    ->toMatch('/@page\s+{\s+size: 210mm 297mm;\s+margin-top: 1cm;\s+margin-right: 2cm;\s+margin-bottom: 3cm;\s+margin-left: 4cm;\s+}/');
