<?php

namespace App\Domain\Category;

use App\Domain\Entity\Categories;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CategoryService
{

    const LEVEL_MAX = 100;

    #[Required]
    public ManagerRegistry $doctrine;
    public $router;

    private array $cache = [];
    public array $list = [];

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function categories(): array
    {
        if (!$this->cache) {
            $this->cache = $this->doctrine->getRepository(Categories::class)->findAll();
        }
        return $this->cache;
    }

    public function getMenuTop(): ?array
    {
        $categories = $this->doctrine->getRepository(Categories::class)->findBy([
            'menu_top' => true,
            'hidden' => false
        ]);

        $menu = [];
        foreach ($categories as $key => $c) {
            $menu[] = [
                'title' => $c->getTitle(),
                'path'  => $c->getPath(),
                'children' => $this->getChildren($c->getId())
            ];
        }
        return $menu;
    }

    private function getChildren( int $id=0 ): ?array
    {
        $menu = [];

        $categories = $this->categories();
        foreach ($categories as $key => $c) {
            if ($c->getParentId()==$id) {
                $menu[] = [
                    'title' => $c->getTitle(),
                    'path' => $c->getPath(),
                    'children' => $this->getChildren($c->getId())
                ];                
            }
        }
        return $menu;
    }

    public function findByPath(string $path): ?Categories
    {
        return $this->doctrine->getRepository(Categories::class)->findOneBy(['path' => $path]);   
    }

    public function findByParent(int $id = 0): ?array
    {
        return $this->doctrine->getRepository(Categories::class)->findBy([
            'parent_id' => $id,
            'hidden' => false
        ]);
    }

    public function getPageMain(): ?array
    {
        return $this->doctrine->getRepository(Categories::class)->findBy([
            // 'page_main_show' => true,
            'hidden' => false
        ]);
    }

    public function getChildrenTree( int $id=0 ): ?array
    {
        $tree = [];

        $categories = $this->categories();
        foreach ($categories as $key => $c) {
            if ($c->getParentId()==$id) {
                $tree[] = [
                    'id' => $c->getId(),
                    'parent_id' => $c->getParentId(),
                    'title' => $c->getTitle(),
                    'children' => $this->getChildrenTree($c->getId())
                ];
            }
        }
        return $tree;
    }

    public function getList( int $id=0 ): ?array
    {
        $categories = $this->categories();
        foreach ($categories as $key => $c) {
            if ($c->getParentId()==$id) {
                $this->list[] = [
                    'id' => $c->getId(),
                    'parent_id' => $c->getParentId(),
                    'title' => $c->getTitle(),
                    'path' => $c->getPath()
                ];
                $this->getList($c->getId());
            }
        }
        return $this->list;
    }


    public function getParents( int $id=0, bool $include_current=false ): ?array
    {
        $parents = [];
        $level = self::LEVEL_MAX;

        while ($id>0 && $level) {
            $categories = $this->categories();
            foreach ($categories as $key => $c) {
                if ($id && $c->getId()==$id) {
                    $parents[] = [
                        'id' => $c->getId(),
                        'title' => $c->getTitle(),
                        'slug' => $c->getSlug(),
                        'path' => $this->router->generate('app_catalog',[
                            'path'=> $c->getPath()
                        ])
                    ];
                    $id = $c->getParentId();
                    break;
                }
            }
            $level--;
        }

        return array_reverse($parents);
    }

    public function getLeft( int $id = 0 ) {
        
        $this->open_ids = $this->getParents($id);
        $categories = $this->categories();
        foreach ($categories as $key => $c) {
            if( $c->getId() == $id ) {
                $this->open_ids[] = [
                    'id' => $c->getId(),
                    'title' => $c->getTitle(),
                    'slug' => $c->getSlug(),
                    'path' => $this->router->generate('app_catalog',[
                        'path'=> $c->getPath()
                    ])
                ];
                break;
            }
        }

        $this->menu = [];
        $this->level = 0;
        $this->getMenuLevel();
        return $this->menu;
    }

    private function getMenuLevel( int $parent_id = 0 )
    {
        if( $this->level > self::LEVEL_MAX ) {
            return;
        }
        $categories = $this->categories();
        foreach ($categories as $key => $catalog) {
            if( $catalog->getParentId() == $parent_id ) {

                $open = false;
                foreach ($this->open_ids as $key => $cat_open) {
                    if( $cat_open['id'] == $catalog->getId() ) {
                        $open = true;
                        break;
                    }
                }

                $item['open']  = $open;
                $item['level'] = $this->level;
                $item['path'] = $this->router->generate('app_catalog',[
                    'path'=> $catalog->getPath()
                ]);
                $item['title'] = $catalog->getTitle();
                $this->menu[] = $item;

                if( $open ) {
                    $this->level++;
                    $this->getMenuLevel($catalog->getId());
                    $this->level--;
                }
            }
        }
    }

    public function findOneById( int $id ): ?Categories
    {
        return $this->doctrine->getRepository(Categories::class)->findOneBy(['id'=>$id]);
    }

    public function serviceRunUpdatePath() {
        $categories = $this->categories();
        foreach ($categories as $key => $cat) {
            $path = '';
            $parents = $this->getParents($cat->getId());
            if ($parents) {
                foreach ($parents as $key => $p) {
                    $path .= $p['slug']."/";
                }
            }
            $cat->setPath($path);
        }
        $this->doctrine->getManager()->flush();
    }
    
}
