from flask import Flask, request, redirect
from flask_restful import Resource, Api
#from flask_cors import CORS
import os
import pymysql
#import prediction
import transformers
import torch
from transformers import AutoModel, BertTokenizerFast
from tensorflow import keras
import torch.nn as nn
import numpy as np

app = Flask(__name__)


#cors = CORS(app, resources={r"*": {"origins": "*"}})
api = Api(app)

class BERT_Arch(nn.Module):


    def __init__(self, bert):
        super(BERT_Arch, self).__init__()

        self.bert = bert

        # dropout layer
        self.dropout = nn.Dropout(0.1)

        # relu activation function
        self.relu =  nn.ReLU()

        # dense layer 1
        self.fc1 = nn.Linear(768,512)
        # dense layer 2 (Output layer)
        self.fc2 = nn.Linear(512,2)

        #softmax activation function
        self.softmax = nn.LogSoftmax(dim=1)

    #define the forward pass
    def forward(self, sent_id, mask):

        #pass the inputs to the model
        _, cls_hs = self.bert(sent_id, attention_mask=mask, return_dict=False)

        x = self.fc1(cls_hs)

        x = self.relu(x)

        x = self.dropout(x)

        # output layer
        x = self.fc2(x)

        # apply softmax activation
        x = self.softmax(x)

        return x

class Test(Resource):
    def get(self):
        return 'Welcome to, Test App API!'

    def post(self):
        try:
            value = request.get_json()
            if(value):
                return {'Post Values': value}, 201

            return {"error":"Invalid format."}

        except Exception as error:
            return {'error': error}

bert = AutoModel.from_pretrained('bert-base-uncased')
# Load the BERT tokenizer
tokenizer = BertTokenizerFast.from_pretrained('bert-base-uncased')
device = torch.device('cpu')

model = BERT_Arch(bert)

path = 'saved_weights.pt'

mymodal=model.load_state_dict(torch.load(path))#, map_location=torch.device("cpu"))#, weights_only=True)

#device =torch.set_default_device("cpu")
#device = torch.load(checkpoint, map_location=torch.device('cpu'))


class GetPredictionOutput(Resource):
    def get(self):
        print("GET")
        conn = pymysql.connect(host="localhost", port=8889, user="app", passwd="appuser", database="app_DB")
        cur = conn.cursor() 
        code="select max(text) from table_log where user_name='"+ request.args.get('user') + "'AND created_dt='" +request.args.get('date')+"'"
        print(code)
        cur.execute(code) 
        output = cur.fetchall() 
        print(output)
        for i in output: 
            print(i) 
        print(i[0])
        text_user=i[0];
        # import BERT-base pretrained model
        # BERT is a pretrained contextual model that generates a representation of each word that is based on the other words in the sentence and captures the relationship between words in the text bidirectionally.
        text_list=[];
        text_list.append(text_user);
        print(text_list);
        # tokenize and encode sequences in the test set
        tokens = tokenizer.batch_encode_plus(
            text_list,
            max_length = 69,
            pad_to_max_length=True, # the returned sequences will be padded according to the model’s padding side and padding index, up to their max length. This allows for all the text values to be same length
            truncation=True
        )
        seq = torch.tensor(tokens['input_ids']) #The tokenizer returns a dictionary with all the arguments necessary for its corresponding model to work properly.
        mask = torch.tensor(tokens['attention_mask']) #The attention mask is a binary tensor indicating the position of the padded indices so that the model does not attend to them. For the BertTokenizer, 1 indicates a value that should be attended to, while 0 indicates a padded value.



        #load weights of best model

        pred1 = model(seq.to(device), mask.to(device))
        pred2 = torch.exp(pred1[:,0]).item()
        print(pred2);
        # To close the connection 
        conn.close()
    
        #predict = text 
        #predictOutput = predict
        if (pred2>0.32):
            return 'http://localhost:8000/app/sad_nobackground.jpeg'
        else:
            return 'http://localhost:8000/app/smily_nobackground.jpeg'
        

        #
        #
        #return {'predict':predictOutput}

        #return {"error":"Invalid Method."}

api.add_resource(Test,'/')
api.add_resource(GetPredictionOutput,'/getPredictionOutput')

if __name__ == "__main__":
    port = int(os.environ.get("PORT", 6000))
    app.run(host='0.0.0.0', port=port)
