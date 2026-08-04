<?php

namespace App\Excel\Imports;

use App\Models\GiamGiaModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\{
    ToModel, 
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class GiamGiaImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation
    //SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $loaiGiamGia = $this->KiemTraLoaiGiamGia($row['Loại giảm giá']);
        $ngayHetHan  = $this->KiemTraThoiGian($row['Ngày hết hạn'] ?? null);
        $giaTri      = $row['Giá trị giảm'];

        $this->KiemTraGiaTri($loaiGiamGia, $giaTri);

        //dd($row);
        return new GiamGiaModel([
            'loai_giam_gia' => $loaiGiamGia,
            'ma_giam_gia' => $row['Mã giảm giá'],
            'gia_tri' => $giaTri,
            'ngay_het_han' => $ngayHetHan,
            'trang_thai' => $row['Trạng thái'] ?? 1,
            'ngay_tao' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.Mã giảm giá' => 'required|string|max:255',
            '*.Giá trị giảm' => 'required|numeric|min:0',
            '*.Trạng thái' => 'nullable|in:0,1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Mã giảm giá.unique' => 'Mã giảm giá đã tồn tại trong hệ thống',
        ];
    }

    private function KiemTraLoaiGiamGia($value)
    {
        $value = trim(mb_strtolower($value));

        $map = [
            '0' => 0,
            '1' => 1,

            'giảm giá phần trăm' => 1,
            'giam gia phan tram' => 1,
            'phan tram' => 1,
            '%' => 1,

            'giảm giá cố định' => 0,
            'giam gia co dinh' => 0,
            'co dinh' => 0,
            'cố định' => 0,
        ];

        if (array_key_exists($value, $map)) {
            return $map[$value];
        }

        throw ValidationException::withMessages([
            'Loại giảm giá' => "Loại giảm giá không hợp lệ: {$value}"
        ]);
    }

    private function KiemTraThoiGian($value)
    {
        if (empty($value)) {
            return null;
        }
    
        //Nếu là số serial Excel
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                        ->format('Y-m-d');
        }
    
        $value = trim($value);
    
        //Danh sách kiểu cho phép
        $formats = [
            'j/n/Y',  //1/1/2026
            'd/m/Y',  //01/01/2026
            'j-n-Y',  //1-1-2026
            'd-m-Y',  //01-01-2026
            'Y/m/d',  //2026/03/20
            'Y-m-d',  //2026-03-20
        ];
    
        foreach ($formats as $format) {
            try {
                $date = \Carbon\Carbon::createFromFormat($format, $value);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {

            }
        }
    
        throw \Illuminate\Validation\ValidationException::withMessages([
            'Ngày hết hạn' => "Ngày không đúng định dạng: {$value}"
        ]);
    }

    private function KiemTraGiaTri($loai, $giaTri)
    {
        $giaTri = str_replace(['.', ',', ' '], '', $giaTri);

        if (!is_numeric($giaTri)) {
            throw ValidationException::withMessages([
                'Giá trị giảm' => "Giá trị giảm phải là số"
            ]);
        }

        $giaTri = (float) $giaTri;

        if ($loai == 1) {
            if ($giaTri <= 0 || $giaTri > 100) {
                throw ValidationException::withMessages([
                    'Giá trị giảm' => "Giá trị giảm giá phải từ 0% đến 100%"
                ]);
            }
        }

        if ($loai == 0) {
            if ($giaTri <= 0) {
                throw ValidationException::withMessages([
                    'Giá trị giảm' => "Giá trị giảm giá phải là số dương và lớn hơn 0"
                ]);
            }
        }
    }
}
