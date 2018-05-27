<?php

use App\Models\InvoiceDesign;

class InvoiceDesignsSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();

        $designs = [
            'Clean',
            'Bold',
            'Modern',
            'Plain',
            'Business',
            'Creative',
            'Elegant',
            'Hipster',
            'Playful',
            'Photo',
        ];

        for ($i = 0; $i < count($designs); $i++) {
            $design = $designs[$i];
            $fileName = storage_path() . '/templates/' . strtolower($design) . '.js';
            if (file_exists($fileName)) {
                $pdfmake = file_get_contents($fileName);
                if ($pdfmake) {
                    $record = InvoiceDesign::whereName($design)->first();
                    if (! $record) {
                        $max_id = DB::table('invoice_designs')->max('id');
                        if (! $max_id) {
                            $next_id = $max_id + 1;
                        } else {
                            $next_id = 1;                            
                        }
                        InvoiceDesign::create([
                            'id' => $next_id,
                            'name' => $design,
                        ]);
                    } else {
                        $record->pdfmake = json_encode(json_decode($pdfmake)); // remove the white space
                        $record->save();
                    }
                }
            }
        }

        for ($i = 1; $i <= 3; $i++) {
            $name = 'Custom' . $i;
            $id = $i + 10;

            if (InvoiceDesign::whereName($name)->orWhere('id', '=', $id)->first()) {
                continue;
            }

            InvoiceDesign::create([
                'id' => $id,
                'name' => $name,
            ]);
        }
    }
}
