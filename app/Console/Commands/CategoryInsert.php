<?php

namespace App\Console\Commands;

use Throwable;
use voku\helper\HtmlDomParser;
use Illuminate\Console\Command;
use App\Repositories\ProdactRepository;
use Illuminate\Support\Facades\Config as FacadesConfig;

require_once '../vendor/autoload.php';

class CategoryInsert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Category:Insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert category in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Ask the link:
            // $pageLink = $this->ask('Enter link ');
            $prodactRepository = new ProdactRepository();

            $pageLink = FacadesConfig::get('technosun.link');
            do
            {
                $htmlLink = HtmlDomParser::file_get_html($pageLink);
                $categoryLists = array();

                foreach ($htmlLink -> find('ul.products.columns-4 ') as $ulTag)
                {
                    foreach ($ulTag -> find('li') as $liTag)
                    {

                        $prodactLink = $liTag -> find('a.woocommerce-LoopProduct-link')[0];
                        $prodactImg  = $liTag -> find('img')[0];
                        $prodactName = $liTag -> find('h2.woocommerce-loop-product__title' , 0);

                        $prodact = array(
                            'link' => $prodactLink->href,
                            'image'=> $prodactImg->src ,
                            'name' => $prodactName->innertext
                        );

                        array_push($categoryLists , $prodact);
                    }
                }

                foreach($categoryLists as $categoryList)
                {
                    if (!$prodactRepository->exists($categoryList['link']))
                    {
                        $prodactRepository->createProdact($categoryList);
                    }


                }

                $page = array();

                foreach ($htmlLink -> find('ul.page-numbers') as $ulTag)
                {
                    foreach ($ulTag -> find('li') as $liTag)
                    {
                        $aTag = $liTag -> find('a.next.page-numbers' , 0);
                        array_push($page , $aTag->href);
                    }
                    $pageLink = max($page);
                }



            }while(!empty($pageLink));

            $this->info('Prodacts Insert  Was  Success');



        } catch (Throwable $e) {
            report($e);

            return false;
        }

    }
}
