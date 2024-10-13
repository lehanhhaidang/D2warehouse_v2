<?php

namespace App;

enum ProposeType: string
{
    case DXNTP = 'Đề xuất nhập thành phẩm';
    case DXXTP = 'Đề xuất xuất thành phẩm';
    case DXNNVL = 'Đề xuất nhập nguyên vật liệu';
    case DXXNVL = 'Đề xuất xuất nguyên vật liệu';
}
