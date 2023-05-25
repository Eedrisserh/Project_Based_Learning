class RepliesController < ApplicationController
	before_action :set_reply, only: [:edit, :update]

	
	def create
		parent_message = Message.find(params[:message_id])
		@reply = parent_message.replies.build(reply_params)
	
		if @reply.save
		  redirect_to message_path(parent_message), notice: "Comment was successfully created."
		else
			redirect_to message_path(@reply.message), alert: "Failed to comment."
		end
	  end
	
	def edit
	end
	
	def update
		if @reply.update(reply_params)
			redirect_to message_path(@reply.message), notice: "Comment was successfully updated."
		else
			render 'edit', alert: 'Error updating comment'
		end
	end

	def destroy
		@reply = Reply.find(params[:id])
		@reply.destroy
		redirect_to message_path(@reply.message), notice: "Reply was successfully deleted."
	end
	  
   
	private

	def set_reply
		@message = Message.find(params[:message_id])
		@reply = @message.replies.find(params[:id])
	end
	
	def reply_params
		params.require(:reply).permit(:content)
	end
	
end