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
        Schema::create('user_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('viewable_type'); // Product, Campaign, Training, etc.
            $table->unsignedBigInteger('viewable_id');
            $table->timestamp('first_viewed_at');
            $table->timestamp('last_viewed_at');
            $table->integer('view_count')->default(1);
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            $table->index(['viewable_type', 'viewable_id']);
            $table->unique(['user_id', 'viewable_type', 'viewable_id']);
            $table->index(['user_id', 'last_viewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_views');
    }
}; 