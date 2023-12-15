<?php

use Medilies\CtrlP\AtPage;
use RowBloom\RowBloom\Renderers\Sizing\Length;

test('compilePageCss full assertion')
    ->expect(
        AtPage::new()
            ->margins('1cm 2cm 3cm 4cm')
            ->paperSize(
                null,
                Length::fromDimension('210mm'),
                Length::fromDimension('297mm')
            ) // A4
            ->toString()
    )
    ->toBe('@page { size: 210mm 297mm; margin-top: 1cm; margin-right: 2cm; margin-bottom: 3cm; margin-left: 4cm; }');
