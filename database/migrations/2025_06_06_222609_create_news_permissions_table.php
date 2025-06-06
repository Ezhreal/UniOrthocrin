use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_type_id')->constrained()->cascadeOnDelete();
            $table->boolean('can_view')->default(true);
            $table->timestamps();
            
            $table->unique(['news_id', 'user_type_id']);
            $table->index(['user_type_id', 'can_view']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_permissions');
    }
}; 