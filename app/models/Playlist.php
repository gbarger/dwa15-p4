<?

class Playlist extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlists';

	public $created_at;
	public $updated_at;
	public $user_id;
	public $name;
}