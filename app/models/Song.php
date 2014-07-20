<?

class Song extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs';

	public $created_at;
	public $updated_at;
	public $user_id;
	public $image_path;
	public $title;
	public $artist;
	public $album;
	public $year;
	public $track;
	public $genre;
}