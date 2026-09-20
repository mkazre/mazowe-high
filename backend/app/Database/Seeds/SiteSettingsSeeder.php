<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'site_name'        => 'Mazowe Heights College',
            'site_strapline'   => 'College · Surgite',
            'logo_header'      => '/assets/img/logo.svg',
            'logo_footer'      => '/assets/img/logo-footer.svg',
            'banner_text'      => 'Opening January 2027 — Founding intake now enrolling',
            'address_line'     => 'Chiweshe Road, Mazowe Valley, Mashonaland Central, Zimbabwe',
            'phone'            => '+263 242 000 000',
            'admissions_email' => 'admissions@mazoweheights.ac.zw',
            'contact_email'    => 'info@mazoweheights.ac.zw',
            'facebook_url'     => '',
            'instagram_url'    => '',
            'twitter_url'      => '',
            'footer_note'      => '© ' . date('Y') . ' Mazowe Heights College. Registered with the Ministry of Primary and Secondary Education.',
            'motto'            => 'Surgite — Arise',
            'show_assistant'   => '1',
        ];

        foreach ($settings as $key => $value) {
            $exists = $this->db->table('site_settings')->where('setting_key', $key)->get()->getRow();
            if (! $exists) {
                $this->db->table('site_settings')->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
