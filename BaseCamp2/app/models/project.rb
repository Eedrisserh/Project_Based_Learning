class Project < ApplicationRecord
	validates :project_title, presence: true
	validates :project_content, presence: true
	has_one_attached :attachment
	belongs_to :user
end
