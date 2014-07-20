<?

class Playlist_Item extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlist_items';

	public $created_at;
	public $updated_at;
	public $playlist_id;
	public $song_id;
}