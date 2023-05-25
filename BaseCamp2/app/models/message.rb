class Message < ApplicationRecord
  validates :content, presence: true
  belongs_to :user
  belongs_to :parent_message, class_name: 'Message', optional: true
  has_many :replies, dependent: :destroy
end
