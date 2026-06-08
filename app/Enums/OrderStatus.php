<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DITERIMA = 'pesanan_diterima';
    case DIJEMPUT = 'pesanan_dijemput';
    case MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    case DIPROSES = 'pesanan_diproses';
    case SIAP = 'pesanan_siap';
    case DIANTAR = 'pesanan_diantar';
    case SELESAI = 'pesanan_selesai';
}