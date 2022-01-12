import urllib
from requests_oauthlib import OAuth1
import requests
import sys
import csv

def main():

    # APIの秘密鍵
    CK = 'TiLR1dCCI3P6W9FZXuKOpYo85' # コンシューマーキー
    CKS = '7dY9l8TjARZJlaNr6oztADub0ntTI4x2B69TaEO6S3IgNMwu47' # コンシューマーシークレット
    AT = '1272371880595296256-yvhaFuYEl1gq5zy1K8I4nQlZTU2Z7A' # アクセストークン
    ATS = '0N9zXh9iZEakRI2lKKIK9h6a3KnjoxVcYXY4rY5drJ5cl' # アクセストークンシークレット

    # 検索時のパラメーター
    word = '(#SHIBUYAMELTDOWN)' # 検索ワード ※ ()に囲むとハッシュタグ検索になる
    count = 50 # 一回あたりの検索数(最大100/デフォルトは15)
    range = 150 # 検索回数の上限値(最大180/15分でリセット)
    result_type =  'mixed'
    # ツイート検索・テキストの抽出
    tweets = search_tweets(CK, CKS, AT, ATS, word, count, range, result_type)
    # 検索結果を表示
    print(tweets[0:4])
    with open("./result.csv", "w", newline="") as f:
        writer = csv.writer(f, delimiter=",", quotechar='"', quoting=csv.QUOTE_ALL)
        writer.writerow(["full_text", "created_a", "retweet_count", "favorite_count", "url"])	
        for tweet in tweets:
            writer.writerow(tweet)

def search_tweets(CK, CKS, AT, ATS, word, count, range, result_type):
    # 文字列設定
    word += ' exclude:retweets' # RTは除く
    word = urllib.parse.quote_plus(word)
    # リクエスト
    url = "https://api.twitter.com/1.1/search/tweets.json?lang=ja&trim_user=true&tweet_mode=extended&q="+word+"&result_type="+result_type+"&count="+str(count)
    auth = OAuth1(CK, CKS, AT, ATS)
    response = requests.get(url, auth=auth)
    data = response.json()['statuses']
    print(data[0])
    # 2回目以降のリクエスト
    cnt = 0
    tweets = []
    while True:
        if len(data) == 0:
            break
        cnt += 1
        if cnt > range:
            break
        for tweet in data:
            if 'extended_entities' in tweet:
                if tweet['extended_entities']['media'] != None:
                    tweets.append([tweet['full_text'], tweet['created_at'], tweet['retweet_count'], tweet['favorite_count'], "https://twitter.com/i/web/status/" + tweet['id_str']])
                    maxid = int(tweet["id"]) - 1
        print(maxid)
        url = "https://api.twitter.com/1.1/search/tweets.json?lang=ja&trim_user=true&tweet_mode=extended&q="+word+"&count="+str(count)+"&max_id="+str(maxid)
        response = requests.get(url, auth=auth)
        try:
            data = response.json()['statuses']
        except KeyError: # リクエスト回数が上限に達した場合のデータのエラー処理
            print('上限まで検索しました')
            break
    return tweets
    
if __name__ == '__main__':
    main()