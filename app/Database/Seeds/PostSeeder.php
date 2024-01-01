<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PostSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $startDate = strtotime('2022-01-01 00:00:00'); // Tanggal awal dalam format "Y-m-d H:i:s"
            $endDate = strtotime('2023-12-31 23:59:59');   // Tanggal akhir dalam format "Y-m-d H:i:s"
            $randomTimestamp = rand($startDate, $endDate);
            $data = [
                'post_slug' => url_title(Time::now()->format('Y-m-d H:i:s') . '-' . 'Ini adalah postingan ke', '-', true) . '-' . $i,
                'post_kategori_id' => rand(1, 5),
                'post_judul' => 'Ini adalah postingan ke ' . $i,
                'post_konten' =>
                '<p style="text-align:justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ut imperdiet turpis, quis faucibus nibh. Fusce sed nisl facilisis, ultrices enim a, viverra leo. Praesent eget metus odio. Praesent dapibus in metus porta consequat. Aliquam erat volutpat. Pellentesque porttitor ligula at massa bibendum, et dapibus urna elementum. Nullam molestie quam at mi malesuada facilisis. Nunc et risus nisi. Maecenas a quam ut dolor condimentum tincidunt. Donec et efficitur mauris, nec mollis ex. Suspendisse fermentum lectus nec lectus ultrices tempus. Quisque ultricies laoreet ultrices.</p>

                <p style="text-align:justify;">Proin ut justo efficitur, tincidunt velit sit amet, sagittis metus. Nulla posuere volutpat massa eu consectetur. Integer elementum, nulla vitae malesuada interdum, nulla arcu lacinia lacus, nec scelerisque eros orci ut risus. Aenean augue tortor, fringilla non tellus eget, laoreet scelerisque nulla. Vestibulum a aliquet tellus, sed venenatis nisi. Nunc malesuada ut sapien et fermentum. Mauris scelerisque pellentesque velit. Quisque urna eros, tempus sit amet est commodo, tincidunt consequat purus.</p>

                <p style="text-align:justify;">Praesent interdum quam at nisi bibendum placerat. Morbi ultricies odio in cursus scelerisque. Sed sed justo dolor. Donec vitae nisl elementum, commodo risus sed, condimentum nunc. Morbi eu eros placerat, suscipit eros et, rutrum massa. Sed blandit felis in lacinia rutrum. Aliquam ornare ligula ut urna ultricies, rhoncus molestie nisi cursus. Donec sit amet elementum sapien. Duis ac sapien id mi vestibulum congue. Phasellus egestas molestie lectus, et commodo massa lobortis vitae. Praesent et hendrerit nisi. Nunc feugiat nulla quis iaculis molestie. Vivamus eu congue risus.</p>

                <p style="text-align:justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tempus lectus auctor mattis venenatis. Duis aliquam pulvinar risus et tempor. Donec eget iaculis mi. Ut rhoncus odio non suscipit mollis. Praesent at pulvinar diam. Pellentesque vel augue rutrum, tristique lorem eget, accumsan dolor. Nam tempor, urna eget suscipit efficitur, lectus nulla eleifend purus, ac mollis lacus erat eget justo. Vestibulum eros erat, accumsan ut lobortis blandit, mollis id lorem. Ut posuere, turpis at semper eleifend, quam magna ultrices mauris, vel ullamcorper nunc ante a neque. In ac congue felis. Sed dolor elit, tristique ac libero sit amet, tempor semper odio. Etiam feugiat massa dolor, sit amet tincidunt sapien bibendum non. Phasellus hendrerit, justo ut dapibus feugiat, neque turpis dictum magna, accumsan rhoncus justo diam vel erat. Donec egestas dolor vel suscipit gravida.</p>

                <p style="text-align:justify;">Aenean suscipit porta lectus, quis vulputate enim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Suspendisse non lobortis augue. Fusce cursus nisl molestie, luctus felis ut, rhoncus lorem. Donec varius sit amet turpis non mollis. Nunc ultricies massa nunc, et pellentesque libero ultricies non. Nam sed feugiat tellus. Pellentesque non velit id turpis laoreet molestie fringilla cursus ex. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Quisque facilisis interdum justo ac placerat. Aenean in congue nunc. Maecenas interdum purus vitae metus bibendum lacinia. Curabitur ut aliquet lacus. Praesent vel porta lectus. Curabitur suscipit enim id elit hendrerit tempus. Fusce consectetur erat sed justo suscipit, vel tristique massa malesuada.</p>',
                'post_jenis' => 1,
                'post_media_id' => 1,
                'post_status' => 2,
                'post_user_id' => 1,
                'post_view' => rand(10, 100),
                'post_approve' => 2,
                'post_created_at' => date('Y-m-d H:i:s', $randomTimestamp),
                'post_published_at' => date('Y-m-d H:i:s', $randomTimestamp),
                'post_created_by' => 1,
                'post_published_by' => 1
            ];

            $dummyData[] = $data;
        }
        // Using Query Builder
        $this->db->table('post')->insertBatch($dummyData);
    }
}
