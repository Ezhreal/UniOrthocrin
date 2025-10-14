<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Black Friday Orthocrin 2025',
                'description' => 'Maior campanha de vendas do ano com descontos especiais em toda linha de produtos.',
                'start_date' => '2025-11-20',
                'end_date' => '2025-11-30',
                'visible_franchise_only' => false,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Campanha de Natal - Sonhos em Casa',
                'description' => 'Campanha especial de Natal focada em conforto e bem-estar para toda família.',
                'start_date' => '2025-12-01',
                'end_date' => '2025-12-25',
                'visible_franchise_only' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Páscoa - Renovação do Sono',
                'description' => 'Campanha de Páscoa com foco em renovação e qualidade do sono.',
                'start_date' => '2025-03-20',
                'end_date' => '2025-04-10',
                'visible_franchise_only' => false,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($campaigns as $index => $campaign) {
            $campaignId = DB::table('campaigns')->insertGetId($campaign);

            // Criar Campaign Folders
            $this->createCampaignFolders($campaignId, $index + 1);

            // Criar Campaign Posts
            $this->createCampaignPosts($campaignId, $index + 1);

            // Criar Campaign Videos
            $this->createCampaignVideos($campaignId, $index + 1);

            // Criar Campaign Miscellaneous
            $this->createCampaignMiscellaneous($campaignId, $index + 1);
        }
    }

    private function createCampaignFolders($campaignId, $campaignIndex)
    {
        $folders = [
            [
                'name' => 'Material MG/SP',
                'description' => 'Material de campanha para Minas Gerais e São Paulo',
                'state' => 'MG/SP',
                'files' => [
                    [
                        'name' => 'campaign-folder-mgsp.pdf',
                        'type' => 'pdf',
                        'extension' => 'pdf',
                        'mime_type' => 'application/pdf',
                        'size' => 2048000, // 2MB
                    ],
                    [
                        'name' => 'campaign-folder-mgsp-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 512000, // 500KB
                    ]
                ]
            ],
            [
                'name' => 'Material DF/ES',
                'description' => 'Material de campanha para Distrito Federal e Espírito Santo',
                'state' => 'DF/ES',
                'files' => [
                    [
                        'name' => 'campaign-folder-dfes.pdf',
                        'type' => 'pdf',
                        'extension' => 'pdf',
                        'mime_type' => 'application/pdf',
                        'size' => 1536000, // 1.5MB
                    ],
                    [
                        'name' => 'campaign-folder-dfes-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 384000, // 375KB
                    ]
                ]
            ],
        ];

        foreach ($folders as $folder) {
            $folderId = DB::table('campaign_folders')->insertGetId([
                'campaign_id' => $campaignId,
                'name' => $folder['name'],
                'description' => $folder['description'],
                'state' => $folder['state'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Criar arquivos e relacionar via tabela pivot
            foreach ($folder['files'] as $fileIndex => $fileData) {
                $fileId = DB::table('files')->insertGetId([
                    'name' => $fileData['name'],
                    'path' => "private/campaigns/{$campaignIndex}/folders/" . $fileData['name'],
                    'type' => $fileData['type'],
                    'extension' => $fileData['extension'],
                    'mime_type' => $fileData['mime_type'],
                    'size' => $fileData['size'],
                    'order' => $fileIndex + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Relacionar via tabela pivot
                DB::table('campaign_folder_files')->insert([
                    'campaign_folder_id' => $folderId,
                    'file_id' => $fileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createCampaignPosts($campaignId, $campaignIndex)
    {
        $posts = [
            [
                'name' => 'Post Feed 01',
                'description' => 'Post feed 01 para Instagram',
                'type' => 'feeds',
                'files' => [
                    [
                        'name' => 'campaign-post-feed-01.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 1024000, // 1MB
                    ]
                ]
            ],
            [
                'name' => 'Post Feed 02',
                'description' => 'Post feed 02 para Instagram',
                'type' => 'feeds',
                'files' => [
                    [
                        'name' => 'campaign-post-feed-02.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 896000, // 875KB
                    ]
                ]
            ],
            [
                'name' => 'Post Feed 03',
                'description' => 'Post feed 03 para Instagram',
                'type' => 'feeds',
                'files' => [
                    [
                        'name' => 'campaign-post-feed-03.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 1152000, // 1.125MB
                    ]
                ]
            ],
            [
                'name' => 'Post Feed 04',
                'description' => 'Post feed 04 para Instagram',
                'type' => 'feeds',
                'files' => [
                    [
                        'name' => 'campaign-post-feed-04.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 768000, // 750KB
                    ]
                ]
            ],
            [
                'name' => 'Story MG/SP 01',
                'description' => 'Story MG/SP 01 para Instagram',
                'type' => 'stories_mg_sp',
                'files' => [
                    [
                        'name' => 'campaign-story-mgsp-01.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 512000, // 500KB
                    ]
                ]
            ],
            [
                'name' => 'Story MG/SP 02',
                'description' => 'Story MG/SP 02 para Instagram',
                'type' => 'stories_mg_sp',
                'files' => [
                    [
                        'name' => 'campaign-story-mgsp-02.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 640000, // 625KB
                    ]
                ]
            ],
            [
                'name' => 'Story MG/SP 03',
                'description' => 'Story MG/SP 03 para Instagram',
                'type' => 'stories_mg_sp',
                'files' => [
                    [
                        'name' => 'campaign-story-mgsp-03.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 448000, // 437.5KB
                    ]
                ]
            ],
            [
                'name' => 'Story MG/SP 04',
                'description' => 'Story MG/SP 04 para Instagram',
                'type' => 'stories_mg_sp',
                'files' => [
                    [
                        'name' => 'campaign-story-mgsp-04.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 576000, // 562.5KB
                    ]
                ]
            ],
            [
                'name' => 'Story DF/ES 01',
                'description' => 'Story DF/ES 01 para Instagram',
                'type' => 'stories_df_es',
                'files' => [
                    [
                        'name' => 'campaign-story-dfes-01.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 384000, // 375KB
                    ]
                ]
            ],
            [
                'name' => 'Story DF/ES 02',
                'description' => 'Story DF/ES 02 para Instagram',
                'type' => 'stories_df_es',
                'files' => [
                    [
                        'name' => 'campaign-story-dfes-02.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 512000, // 500KB
                    ]
                ]
            ],
            [
                'name' => 'Story DF/ES 03',
                'description' => 'Story DF/ES 03 para Instagram',
                'type' => 'stories_df_es',
                'files' => [
                    [
                        'name' => 'campaign-story-dfes-03.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 448000, // 437.5KB
                    ]
                ]
            ],
            [
                'name' => 'Story DF/ES 04',
                'description' => 'Story DF/ES 04 para Instagram',
                'type' => 'stories_df_es',
                'files' => [
                    [
                        'name' => 'campaign-story-dfes-04.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 640000, // 625KB
                    ]
                ]
            ],
        ];

        foreach ($posts as $post) {
            $postId = DB::table('campaign_posts')->insertGetId([
                'campaign_id' => $campaignId,
                'name' => $post['name'],
                'description' => $post['description'],
                'type' => $post['type'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Criar arquivos e relacionar via tabela pivot
            foreach ($post['files'] as $fileIndex => $fileData) {
                $fileId = DB::table('files')->insertGetId([
                    'name' => $fileData['name'],
                    'path' => "campaigns/{$campaignIndex}/posts/" . $fileData['name'],
                    'type' => $fileData['type'],
                    'extension' => $fileData['extension'],
                    'mime_type' => $fileData['mime_type'],
                    'size' => $fileData['size'],
                    'order' => $fileIndex + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Relacionar via tabela pivot
                DB::table('campaign_post_files')->insert([
                    'campaign_post_id' => $postId,
                    'file_id' => $fileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createCampaignVideos($campaignId, $campaignIndex)
    {
        $videos = [
            [
                'name' => 'Reel Promocional Principal',
                'description' => 'Reel principal para Instagram com foco na campanha',
                'type' => 'reels',
                'files' => [
                    [
                        'name' => 'campaign-reel-01.mp4',
                        'type' => 'video',
                        'extension' => 'mp4',
                        'mime_type' => 'video/mp4',
                        'size' => 5120000, // 5MB
                    ],
                    [
                        'name' => 'campaign-reel-01-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 256000, // 250KB
                    ]
                ]
            ],
            [
                'name' => 'Reel Promocional Secundário',
                'description' => 'Reel secundário para Instagram com foco na campanha',
                'type' => 'reels',
                'files' => [
                    [
                        'name' => 'campaign-reel-02.mp4',
                        'type' => 'video',
                        'extension' => 'mp4',
                        'mime_type' => 'video/mp4',
                        'size' => 4096000, // 4MB
                    ],
                    [
                        'name' => 'campaign-reel-02-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 192000, // 187.5KB
                    ]
                ]
            ],
            [
                'name' => 'Reel Promocional Terciário',
                'description' => 'Reel terciário para Instagram com foco na campanha',
                'type' => 'reels',
                'files' => [
                    [
                        'name' => 'campaign-reel-03.mp4',
                        'type' => 'video',
                        'extension' => 'mp4',
                        'mime_type' => 'video/mp4',
                        'size' => 3584000, // 3.5MB
                    ],
                    [
                        'name' => 'campaign-reel-03-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 128000, // 125KB
                    ]
                ]
            ],
            [
                'name' => 'Vídeo Marketing Principal',
                'description' => 'Vídeo principal da campanha para marketing',
                'type' => 'marketing_campaigns',
                'files' => [
                    [
                        'name' => 'campaign-video-01.mp4',
                        'type' => 'video',
                        'extension' => 'mp4',
                        'mime_type' => 'video/mp4',
                        'size' => 10240000, // 10MB
                    ],
                    [
                        'name' => 'campaign-video-01-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 384000, // 375KB
                    ]
                ]
            ],
            [
                'name' => 'Vídeo Marketing Secundário',
                'description' => 'Vídeo secundário da campanha para marketing',
                'type' => 'marketing_campaigns',
                'files' => [
                    [
                        'name' => 'campaign-video-02.mp4',
                        'type' => 'video',
                        'extension' => 'mp4',
                        'mime_type' => 'video/mp4',
                        'size' => 8192000, // 8MB
                    ],
                    [
                        'name' => 'campaign-video-02-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 320000, // 312.5KB
                    ]
                ]
            ],
            [
                'name' => 'Vídeo Marketing Terciário',
                'description' => 'Vídeo terciário da campanha para marketing',
                'type' => 'marketing_campaigns',
                'files' => [
                    [
                        'name' => 'campaign-video-03.mp4',
                        'type' => 'video',
                        'extension' => 'mp4',
                        'mime_type' => 'video/mp4',
                        'size' => 6144000, // 6MB
                    ],
                    [
                        'name' => 'campaign-video-03-thumb.jpg',
                        'type' => 'image',
                        'extension' => 'jpg',
                        'mime_type' => 'image/jpeg',
                        'size' => 256000, // 250KB
                    ]
                ]
            ],
        ];

        foreach ($videos as $video) {
            $videoId = DB::table('campaign_videos')->insertGetId([
                'campaign_id' => $campaignId,
                'name' => $video['name'],
                'description' => $video['description'],
                'type' => $video['type'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Criar arquivos e relacionar via tabela pivot
            foreach ($video['files'] as $fileIndex => $fileData) {
                $fileId = DB::table('files')->insertGetId([
                    'name' => $fileData['name'],
                    'path' => "campaigns/{$campaignIndex}/videos/" . $fileData['name'],
                    'type' => $fileData['type'],
                    'extension' => $fileData['extension'],
                    'mime_type' => $fileData['mime_type'],
                    'size' => $fileData['size'],
                    'order' => $fileIndex + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Relacionar via tabela pivot
                DB::table('campaign_video_files')->insert([
                    'campaign_video_id' => $videoId,
                    'file_id' => $fileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createCampaignMiscellaneous($campaignId, $campaignIndex)
    {
        $miscellaneous = [
            [
                'name' => 'Spot de Rádio',
                'description' => 'Spot de rádio da campanha',
                'type' => 'spot',
                'files' => [
                    [
                        'name' => 'campaign-spot.mp3',
                        'type' => 'audio',
                        'extension' => 'mp3',
                        'mime_type' => 'audio/mpeg',
                        'size' => 2048000, // 2MB
                    ]
                ]
            ],
            [
                'name' => 'Tag Promocional',
                'description' => 'Tag para redes sociais',
                'type' => 'tag',
                'files' => [
                    [
                        'name' => 'campaign-tag.pdf',
                        'type' => 'pdf',
                        'extension' => 'pdf',
                        'mime_type' => 'application/pdf',
                        'size' => 512000, // 500KB
                    ]
                ]
            ],
            [
                'name' => 'Sticker Promocional',
                'description' => 'Sticker para WhatsApp',
                'type' => 'sticker',
                'files' => [
                    [
                        'name' => 'campaign-sticker.pdf',
                        'type' => 'pdf',
                        'extension' => 'pdf',
                        'mime_type' => 'application/pdf',
                        'size' => 768000, // 750KB
                    ]
                ]
            ],
            [
                'name' => 'Roteiro da Campanha',
                'description' => 'Roteiro completo da campanha',
                'type' => 'script',
                'files' => [
                    [
                        'name' => 'campaign-script.pdf',
                        'type' => 'pdf',
                        'extension' => 'pdf',
                        'mime_type' => 'application/pdf',
                        'size' => 1024000, // 1MB
                    ]
                ]
            ],
        ];

        foreach ($miscellaneous as $item) {
            $itemId = DB::table('campaign_miscellaneous')->insertGetId([
                'campaign_id' => $campaignId,
                'name' => $item['name'],
                'description' => $item['description'],
                'type' => $item['type'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Criar arquivos e relacionar via tabela pivot
            foreach ($item['files'] as $fileIndex => $fileData) {
                $fileId = DB::table('files')->insertGetId([
                    'name' => $fileData['name'],
                    'path' => "campaigns/{$campaignIndex}/miscellaneous/" . $fileData['name'],
                    'type' => $fileData['type'],
                    'extension' => $fileData['extension'],
                    'mime_type' => $fileData['mime_type'],
                    'size' => $fileData['size'],
                    'order' => $fileIndex + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Relacionar via tabela pivot
                DB::table('campaign_miscellaneous_files')->insert([
                    'campaign_miscellaneous_id' => $itemId,
                    'file_id' => $fileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
