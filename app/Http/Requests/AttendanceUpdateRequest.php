<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'required|date_format:H:i|after:clock_in_time',
            'break_start_time' => 'nullable|date_format:H:i|after_or_equal:clock_in_time|before:clock_out_time',
            'break_end_time' => 'nullable|date_format:H:i|after:break_start_time|before_or_equal:clock_out_time',
            'note' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'clock_in_time.required' => '出勤時間を入力してください',
            'clock_in_time.date_format' => '出勤時間は正しい形式で入力してください',
            'clock_out_time.required' => '退勤時間を入力してください',
            'clock_out_time.date_format' => '退勤時間は正しい形式で入力してください',
            'clock_out_time.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_start_time.date_format' => '休憩開始時間は正しい形式で入力してください',
            'break_start_time.after_or_equal' => '休憩時間が不適切な値です',
            'break_start_time.before' => '休憩時間が不適切な値です',
            'break_end_time.date_format' => '休憩終了時間は正しい形式で入力してください',
            'break_end_time.after' => '休憩終了時間は休憩開始時間より後である必要があります',
            'break_end_time.before_or_equal' => '休憩時間もしくは退勤時間が不適切な値です',
            'note.required' => '備考を記入してください',
            'note.string' => '備考は文字列で入力してください',
            'note.max' => '備考は1000文字以内で入力してください',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // 休憩開始時間と終了時間の組み合わせチェック
            if (!empty($data['break_start_time']) && empty($data['break_end_time'])) {
                $validator->errors()->add('break_end_time', '休憩開始時間を入力した場合は、休憩終了時間も入力してください');
            }

            if (empty($data['break_start_time']) && !empty($data['break_end_time'])) {
                $validator->errors()->add('break_start_time', '休憩終了時間を入力した場合は、休憩開始時間も入力してください');
            }
        });
    }
}
