<?php

use Medilies\CtrlP\AtPage;
use Medilies\CtrlP\CtrlP;
use RowBloom\RowBloom\Renderers\Sizing\Length;

test('proxy to AtPage')
    ->expect(CtrlP::html('@ctrl_p_css')->margins('1cm 2cm 3cm 4cm')
        ->paperSize(
            null,
            Length::fromDimension('210mm'),
            Length::fromDimension('297mm')
        ) // A4
        ->get())
    ->toBeString()
    ->toContain('@page');

test('atPageRule() with callable')
    ->expect(
        CtrlP::html('@ctrl_p_css')->atPageRule('x', function (AtPage $atPage) {
            $atPage->margins('1cm 2cm 3cm 4cm')
                ->paperSize(
                    null,
                    Length::fromDimension('210mm'),
                    Length::fromDimension('297mm')
                ); // A4
        })->get()
    )
    ->toBeString()
    ->toContain('@page');

test('atPageRule() with instanceof AtPage')
    ->expect(
        CtrlP::html('@ctrl_p_css')
            ->atPageRule('x', AtPage::new()->margins('1cm 2cm 3cm 4cm')
                ->paperSize(
                    null,
                    Length::fromDimension('210mm'),
                    Length::fromDimension('297mm')
                ) // A4
            )
            ->get()
    )
    ->toBeString()
    ->toContain('@page');
