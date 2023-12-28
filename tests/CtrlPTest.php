<?php

use Medilies\CtrlP\AtPage;
use Medilies\CtrlP\CtrlP;
use RowBloom\RowBloom\Renderers\Sizing\Length;

test('proxy to AtPage')
    ->expect(CtrlP::html('@CtrlP')->margins('1cm 2cm 3cm 4cm')
        ->paperSize(
            Length::fromDimension('210mm'),
            Length::fromDimension('297mm')
        ) // A4
        ->get())
    ->toBeString()
    ->toContain('@page');

test('atPageRule() with callable')
    ->expect(
        CtrlP::html('@CtrlP')->atPageRule('x', function (AtPage $atPage) {
            $atPage->margins('1cm 2cm 3cm 4cm')
                ->paperSize('210mm', '297mm');
        })->get()
    )
    ->toBeString()
    ->toContain('@page');

test('atPageRule() with instanceof AtPage')
    ->expect(
        CtrlP::html('@CtrlP')
            ->atPageRule('x', AtPage::new()->margins('1cm 2cm 3cm 4cm')
                ->paperSize(
                    Length::fromDimension('210mm'),
                    Length::fromDimension('297mm')
                )
            )
            ->get()
    )
    ->toBeString()
    ->toContain('@page');

test('Render template')
    ->expect(CtrlP::template('<?= $foo ?>', ['foo' => 1])->autoPrint(false)->get())
    ->toBe('1');
