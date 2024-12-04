<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';
    public $timestamps = false;

    protected $fillable = ['user_id', 'product_id', 'rating', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:Product,id',
        'rating' => 'required|integer|min:0|max:5',
        'description' => 'required|string|max:500',
    ]);

    // Verificar se já existe uma review para o produto e o usuário
    $existingReview = Review::where('user_id', auth()->id())
                            ->where('product_id', $request->product_id)
                            ->first();

    if ($existingReview) {
        return back()->with('error', 'Você já fez uma avaliação para este produto. Edite ou exclua a existente.');
    }

    // Criar a nova review
    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'rating' => $request->rating,
        'description' => $request->description,
    ]);

    return back()->with('success', 'Avaliação criada com sucesso!');
}

}
