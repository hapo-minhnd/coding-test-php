<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\View\JsonView;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index', 'view']);

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $articles = $this->paginate($this->Articles)->toArray();
        foreach ($articles as $article) {
            $likeCount = $this->Articles->Likes->find()
                ->where(['article_id' => $article->id])
                ->count();
            $article->like_count = $likeCount;
        }

        $this->set(compact('articles'));
        $this->viewBuilder()->setOption('serialize', ['articles']);
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => ['Users'],
        ]);
        $likeCount = $this->Articles->Likes->find()
            ->where(['article_id' => $article->id])
            ->count();
        $article->like_count = $likeCount;
        $this->set(compact('article'));
        $this->viewBuilder()->setOption('serialize', ['article']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $article = $this->Articles->newEntity($this->request->getData());
        if ($this->Articles->save($article)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'recipe' => $article,
        ]);
        $this->viewBuilder()->setOption('serialize', ['article', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $article = $this->Articles->get($id);
        $article = $this->Articles->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'article' => $article,
        ]);
        $this->viewBuilder()->setOption('serialize', ['article', 'message']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $recipe = $this->Articles->get($id);
        $message = 'Deleted';
        if (!$this->Articles->delete($recipe)) {
            $message = 'Error';
        }
        $this->set('message', $message);
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    public function like($id = null)
    {
        $this->request->allowMethod('post');
        $userId = $this->Authentication->getIdentity()->id;
        // Validate
        $likeTable = TableRegistry::getTableLocator()->get('Likes');
        $existingLike = $likeTable->find()
            ->where([
                'user_id' => $userId,
                'article_id' => $id
            ])
            ->first();

        if ($existingLike) {
            $message = 'Like already exists in the database.';
        } else {
            $like = $likeTable->newEntity([
                'user_id' => $userId,
                'article_id' => $id,
            ]);
            if ($likeTable->save($like)) {
                $message = 'Liked';
            } else {
                $message = 'Error';
            }
        }

        $this->set([
            'message' => $message,
            '_serialize' => 'message',
        ]);

        $this->viewBuilder()->setOption('serialize', ['message']);
    }
}
