class CreateMessages < ActiveRecord::Migration[7.0]
  def change
    create_table :messages do |t|
      t.text :content
      t.references :user, null: false, foreign_key: true
      t.references :parent_message, null: true, foreign_key: { to_table: :messages }
      t.timestamps
    end
  end
end
