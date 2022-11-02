<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use App\Models\Book;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function canGetBooks()
    {

        // $id = self::insert();
        // $id2 = self::insert();
        // $id3 = self::insert();
   
        // $all_books = self::getAll();
        // dd($all_books);

        //$this->assertTrue(true);
        $books = Book::factory(4)->create();


        if($books->count() > 0){
            $response = $this->getJson(route('books.index'));
            $response->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[1]->title
            ]);
        }else{
            dd(['No Hay']);
        }
    }

    /** @test */
    public function canGetBook()
    {

        $book = Book::factory()->create();


        $response = $this->getJson(route('books.show',$book));
        $response->assertJsonFragment([
            'title' => $book->title
        ]);
        
    }

    /** @test */
    public function canInsertBook()
    {
        $this->postJson(route('books.store'),[])
            ->assertJsonValidationerrorFor('title');

        $this->postJson(route('books.store'),[
            'title' => 'New Book'
        ])->assertJsonFragment([
            'title' => 'New Book'
        ]);
        
        $this->assertDatabaseHas('books',[
            'title' => 'New Book'
        ]);
    }

    /** @test */
    public function canUpdateBook()
    {

        $book = Book::factory()->create();

        $this->patchJson(route('books.update',$book),[])
            ->assertJsonValidationerrorFor('title');

        $this->patchJson(route('books.update',$book),[
            'title' => 'New Book Edited'
        ])->assertJsonFragment([
            'title' => 'New Book Edited'
        ]);
        
        $this->assertDatabaseHas('books',[
            'title' => 'New Book Edited'
        ]);
    }

    /** @test */
    public function canDeleteBook()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();
        
        $this->assertDatabaseCount('books',0);
    }


    private function insert()
    {
        
        $response = $this->post(route('books.store'), [
            'title' => 'aaaaaaa'
        ]);
        
        return $response['id'];
    }

    private function getAll()
    {
        
        $response = $this->getJson(route('books.index'));
        
        return $response;
    }

}
